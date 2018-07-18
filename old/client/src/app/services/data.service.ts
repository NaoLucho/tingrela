import { Injectable } from '@angular/core'
import { Http, Headers, RequestOptions, Response } from '@angular/http'
import { AuthHttp } from 'angular2-jwt'

import { GlobalsService } from './globals.service'

import { Observable } from 'rxjs/Rx'
//import 'rxjs/add/observable/throw';

// Import RxJs required methods
//import 'rxjs/add/operator/map'
//import 'rxjs/add/operator/catch'

import { Subject } from 'rxjs/Subject'


import { Type } from './../objects/type'
//import { Product } from './../objects/product'
/*import 'rxjs/add/operator/toPromise'
*/
@Injectable()

export class DataService {

	private data : Type[]
	private data$ = new Subject<any>()
	private category$ = new Subject<any>()
	private product$ = new Subject<any>()
  private baseUrl
  private urldatatypes = "types"
  private subject = new Subject<any>()
  //private basketProducts : Product[];


  constructor(private http: Http,
    private globalsService: GlobalsService
    //, private authHttp: AuthHttp //=> A activer pour l'authentification Client pour accéder aux url server protégés
    ) { 
      this.baseUrl = globalsService.getUrl()
    }

  loadData() : Observable<Type[]> {

		/*Au moment du chargement le l'App on recherche les données soit sur
		le serveur soit dans le service si elles sont déjà téléchargées */
		if (this.data) {
			return Observable.of(this.data);
		} else {
      let headers = new Headers({ 'content-type': 'application/json' });
      //l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
      //let headers = new Headers({ 'content-type': 'application/x-www-form-urlencoded' });

      let options = new RequestOptions({ headers: headers });
			return this.http.get(this.baseUrl+this.urldatatypes, options)
			.map((res: Response) =>
				res.json())
			.do(data => {
				//On enregistre sur une variable localle toutes les données chargées
				this.data = data
				//On envoie les données à tous les subscribes de l'observer "subject"
				this.sendData(data)
			})
      .catch((error:any) => Observable.throw(
        console.log(error)
        //error.json().error || 'Server error in loadData() of data service'
        ))
		}
	}

	//Permet à tous les components d'appeler le subscribe sur les data une fois le sendData lancé

  getDataSubscribed(): Observable<any> {
    return this.data$.asObservable();
  }

  initData() {
    this.data$.next(this.data);
  }

  //On envoie les données à tous les subscribes de l'observer "subject"
  sendData(data) {
    this.data$.next(data);
  }

  getCategorySubscribed(): Observable<any> {
    return this.category$.asObservable();
  }

  sendCategory(category) {
    this.category$.next(category);
  }

  getProduct(id) {
    if(this.data) {
      for( let type of this.data) {
        for( let category of type.categories) {
          for( let product of category.products) {
            if (id == product.id) {
              return product
            }
          }
        }
      }
    }
  }

  getType()
  {
    return this.data;
  }


  // //@Rest\Post("/basket/add/{productid}/{qte}, defaults={"qte" = 1}")
  // postBasket(productid, qte)
  // {
    //   let posturl = "http://localhost/gjchoc/server/web/app_dev.php/api/basket";
    //   if(productid)
    //     posturl = posturl+productid;
    //   if(qte)
    //     posturl = posturl+"/"+qte;

    //   // let headers = new Headers({ 'Content-Type': 'application/json' });
    //   // let options = new RequestOptions({ headers: headers });
    //   return this.http.put(posturl, null)
    //     .map(res => res.json())
    //     .do(data => {this.data = data; console.log(data);})
    //     .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    // }

    // private handleError(error: any): Promise<any> {
      //   console.error('An error occurred', error); // for demo purposes only
      //   return Promise.reject(error.message || error);
      // }

	/*getDataAuth() {
		if (this.data) {
			return Observable.of(this.data);
		} else {
			// ...using get request
			return this.authHttp.get(this.url)
			.map(res => res.json())
			//.do(data => this.data = data)
			.do(data => {this.data = data; console.log(data);})
			.catch((error:any) => Observable.throw(error.json().error || 'Server error'));
		}
	}*/

/*
//---------------------------Exemples à conserver -------------------------//
  // Récupérer les Types seulement (sans les catégories et produits)
  getTypesOnly() {
    if (this.data) {
      return Observable.of(this.data);
    } else {
      // ...using get request
      return this.http.get(this.baseUrl+'typesOnly')
      .map(res => res.json())
      //.do(data => this.data = data)
      .do(data => {this.data = data; console.log(data);})
      .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }
  }

  // Récupérer les Types complet en passant vérifiant l'authentification token (dans le cas ou l'accès est sécurisé)
  getTypesAuth() {
    if (this.data) {
      return Observable.of(this.data);
    } else {
      // ...using get request
      return this.authHttp.get(this.baseUrl+ 'types')
      .map(res => res.json())
      //.do(data => this.data = data)
      .do(data => {this.data = data; console.log(data);}) //ACTIVATION Console.log de data
      .catch((error:any) => Observable.throw(error.json().error || 'Server error'));
    }
  }
  */

  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }

}
