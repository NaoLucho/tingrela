import { Injectable } from '@angular/core'
import { Http, Headers, Response, RequestOptions } from '@angular/http'

import { Product } from './../objects/product'

import { Observable } from 'rxjs/Rx'
import { Subject } from 'rxjs/Subject'

import { GlobalsService } from './globals.service'

@Injectable()

export class BasketService {

  private headers = new Headers({ 'Content-Type': 'application/json' });

  private baseUrl

  // private types;
  private basketProducts: Product[];
  private basketlist: any[];
  private tva: number;
  constructor(private http: Http,
    private globals: GlobalsService
  ) {
    this.baseUrl = globals.getUrl()
  }

  // GET Ecommerce Config (TVA)
  getTva() {
    if (this.tva) {
      return Observable.of(this.tva);
    } else {
      const getTvaUrl = this.baseUrl + 'tva';
      const headers = new Headers({ 'content-type': 'application/json' });
      const options = new RequestOptions({ headers: headers });
      return this.http.get(getTvaUrl, options)
        .map(res => res.json().tva) // Ce qui est retourné
        .do(data => {
          this.tva = data.tva
        })
        .catch(this.handleErrorObservable)
      // (error: any) => Observable.throw(error.json().error || 'Server error'));
      // console.log(error));

    }
  }

  // testPostType()
  // {
  //   let typedata = [
  //     {type: "testType"},
  //     {description: "desctype"}
  //   ]
  //   console.log("post: " + JSON.stringify(typedata));
  //   let headers = new Headers({ 'Content-Type': 'application/json' });
  //   let options = new RequestOptions({ headers: headers });
  //   return this.http.post("http://localhost/GJchoc/server/web/app_dev.php/api/admin/types", "", options)
  //     .map(res => res.json())
  //     .subscribe(data => console.log(data),
  //       error => console.log(error));
  //     // .do(data => {console.log(data);})
  //     // .catch((error:any) => Observable.throw(error.json().error || 'Server error'));

  // }
  // ************************* NON utilisé
  // Avec une gestion du Panier dans la $Session côté server
  // c'est un exemple, NOUS OPTONS PLUTOT POUR UNE GESTION DU BASKET COTE CLIENT, qui sera envoyé coté server dans la commande
  postBasket(productid, qte) {
    let postAddBasket = this.baseUrl + 'basket/add';
    if (productid) {
      postAddBasket = postAddBasket + '/' + productid;


      if (qte) {
        postAddBasket = postAddBasket + '/' + qte;
        return this.http.put(postAddBasket, null)
        .map(res => res.json())
        .do(data => {
          this.basketProducts = data;
          // console.log(data);
        })
        .catch(this.handleErrorObservable)
      }
      // (error: any) => Observable.throw(error.json().error || 'Server error'));
    }

  }

  addProductBasket(productId, qte) {
    // console.log("addProduct("+productId+","+qte+")")
    // let types = this.dataService.getType()
    // let basket;
    // basket = JSON.parse(localStorage.getItem('basket'));
    // console.log(basket)
    // if( !basket ) basket=[];

    this.basketlist = JSON.parse(localStorage.getItem('basketlist'));
    // console.log(basketlist)
    if (!this.basketlist) {
      this.basketlist = [];
    }

    // if (!qte) qte = 1;
    if (qte && qte >= 0) {
      // basket[productId] = qte;
      // localStorage.setItem('basket', JSON.stringify(basket))
      const basketItem = { id: productId, qte: qte };
      if (this.basketlist) {
        this.basketlist = this.basketlist.filter(myObj => myObj.id !== productId);
      }
      if (qte > 0) {
        this.basketlist.push(basketItem);
      }
      localStorage.setItem('basketlist', JSON.stringify(this.basketlist))
      // console.log(basket)
      // console.log(this.basketlist);
    }
    return this.basketlist
  }

  getBasket() {
    if (!this.basketlist) {
      this.basketlist = JSON.parse(localStorage.getItem('basketlist'));
    }
    return this.basketlist
  }

  getBasketlistProducts() {
    if (!this.basketlist) {
      this.basketlist = JSON.parse(localStorage.getItem('basketlist'));
    }

    if (this.basketlist && this.basketlist.length > 0) {
      // console.log(this.basketlist);
      const getBasketUrl = this.baseUrl + 'basket/products';
      const params = new URLSearchParams();
      let paramsStr = '?productsid='

      this.basketlist.forEach(elem => {
        if (elem.qte > 0) {
          paramsStr = paramsStr + elem.id + ';';
        }
      })

      // console.log(basketlist.toString()+" toString = "+paramsStr)
      // console.log(getBasketUrl + paramsStr);
      const headers = new Headers({ 'Content-Type': 'application/json' });
      const options = new RequestOptions({ headers: headers });
      console.log(getBasketUrl + paramsStr)
      return this.http.get(getBasketUrl + paramsStr, options)
        .map(res => res.json())
        .do(data => {
          this.basketProducts = data;
        })
        .catch(this.handleErrorObservable); // this.handleServerError);
    } else {
      return Observable.of([]); // Observable.empty<Response>();
    }
  }

  getBasketPrice() {
    let totalHT = 0;
    this.basketlist.forEach(basketItem => {
      if (basketItem.qte && basketItem.qte >= 0 && this.basketProducts) {
        for (let p = this.basketProducts.length - 1; p >= 0; p--) {
          // console.log("this.products[p "+p+"].id = " + this.products[p].id + " == i :"+i+" > "+(this.products[p].id == i));
          if (this.basketProducts[p].id === basketItem.id) {
            // console.log((Number(basketItem.qte)))
            totalHT = totalHT + (Number(this.basketProducts[p].price) * (Number(basketItem.qte) / Number(this.basketProducts[p].step)));
            // console.log("this.products[p].price "+this.products[p].price+" * this.basket[i] "+this.basket[i]+"="+this.totalHT);

          }
        }
      }
    })
    // console.log('totalHT' + totalHT)
    return totalHT;
  }

  private handleErrorObservable(error: Response | any) {
    // console.error(error.message || error);
    return Observable.throw(error.message || error);
  }

  private handleServerError(error: Response) {
    console.log('sever error:', error)
    if (error instanceof Response) {
      return Observable.throw(error.json().error || 'backend server json error');
    }
    return Observable.throw(error || 'backend server error2');
  }

}
