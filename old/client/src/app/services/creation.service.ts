import { Injectable } from '@angular/core'
import { Http, Headers } from '@angular/http'
import { Response } from '@angular/http'
import { AuthHttp } from 'angular2-jwt'

import { Observable } from 'rxjs/Rx'

import { Subject } from 'rxjs/Subject'

import { GlobalsService } from './globals.service'
import { Creation } from './../objects/creation'

@Injectable()

export class CreationService {

	private headers = new Headers({'content-type': 'application/json'});

	private creations : Creation[];
	private creations$ = new Subject<any>();
    private baseUrl;
    private urlcreations = "creations"

		constructor(private http: Http,
			private globals: GlobalsService
		) {
			this.baseUrl = globals.getUrl()
		}
    //, private authHttp: AuthHttp //=> A activer pour l'authentification Client pour accéder aux url server protégés

  loadCreations() : Observable<Creation[]> {

		/*Au moment du chargement le l'App on recherche les données soit sur
		le serveur soit dans le service si elles sont déjà téléchargées */
		if (this.creations) {
			return Observable.of(this.creations);

		} else {
			return this.http.get(this.baseUrl+this.urlcreations)
			.map((res: Response) =>
				res.json())
			.do(data => {
				//On enregistre sur une variable localle toutes les données chargées
				this.creations = data
				//On envoie les données à tous les subscribes de l'observer "subject"
				this.sendData(data)
			})
			.catch((error:any) => Observable.throw(error.json().error || 'Server error'));
		}
	}

	//Permet à tous les components d'appeler le subscribe sur les data une fois le sendData lancé

  getCreationsSubscribed(): Observable<any> {
    return this.creations$.asObservable();
  }

  initCreations() {
    this.creations$.next(this.creations);
  }

  //On envoie les données à tous les subscribes de l'observer "subject"
  sendData(creations) {

    this.creations$.next(creations);

  }

  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }

}
