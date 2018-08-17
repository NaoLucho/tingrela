import { Injectable } from '@angular/core'
import { Http, Headers, RequestOptions, Response } from '@angular/http'

import { GlobalsService } from './globals.service'

import { Observable } from 'rxjs/Rx'

import { Subject } from 'rxjs/Subject'

import { Type } from './../objects/type'

@Injectable()

export class DataService {

    private data: Type[]
    private data$ = new Subject<any>()
    private category$ = new Subject<any>()
    private product$ = new Subject<any>()
    private baseUrl
    private urldatatypes = 'types'
    private subject = new Subject<any>()


  constructor(private http: Http,
    private globalsService: GlobalsService
    // private authHttp: AuthHttp //=> A activer pour l'authentification Client pour accéder aux url server protégés
    ) { 
      this.baseUrl = globalsService.getUrl()
    }

  loadData() : Observable<Type[]> {

        /*Au moment du chargement le l'App on recherche les données soit sur
        le serveur soit dans le service si elles sont déjà téléchargées */
        if (this.data) {
            return Observable.of(this.data);
        } else {
      const headers = new Headers({ 'content-type': 'application/json' });
      // l’en-tête CORS « Access-Control-Allow-Origin » est manquant.
      // let headers = new Headers({ 'content-type': 'application/x-www-form-urlencoded' });

      const options = new RequestOptions({ headers: headers });
            return this.http.get(this.baseUrl+this.urldatatypes, options)
            .map((res: Response) =>
                res.json())
            .do(data => {
                // On enregistre sur une variable locale toutes les données chargées
                this.data = data
                // On envoie les données à tous les subscribes de l'observer "subject"
                this.sendData(data)
            })
      .catch((error:any) => Observable.throw(
        console.log(error)
        // error.json().error || 'Server error in loadData() of data service'
        ))
        }
    }

    // Permet à tous les components d'appeler le subscribe sur les data une fois le sendData lancé

  getDataSubscribed(): Observable<any> {
    return this.data$.asObservable();
  }

  initData() {
    this.data$.next(this.data);
  }

  // On envoie les données à tous les subscribes de l'observer "subject"
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
      for( const type of this.data) {
        for( const category of type.categories) {
          for( const product of category.products) {
            if (id == product.id) {
              return product
            }
          }
        }
      }
    }
  }

  getType() {
    return this.data;
  }

  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }

}
