import { Injectable } from '@angular/core'
import { Http, Headers, RequestOptions } from '@angular/http'

import { Commande } from './../objects/commande'

import { Observable } from 'rxjs/Rx'

import { GlobalsService } from './globals.service'


@Injectable()

export class PaymentService {

  private headers = new Headers({ 'Content-Type': 'application/json' });
  private baseUrl;

  private types;
  private data;
  private error;
  constructor(
    private http: Http,
    private globals: GlobalsService
  ) {
    this.baseUrl = globals.getUrl()
  }

  postPayment(token, commande: Commande, basket: Array<any>, amount: number) {
    const postPayment = this.baseUrl + 'payment';

    const postdata = {
      token: token,
      commande: commande,
      basket: basket,
      amount: amount
    };
    const headers = new Headers({ 'Content-Type': 'application/json' });
    const options = new RequestOptions({ headers: headers });
    return this.http.post(postPayment, postdata, options)
      .map(res => res.json())
      .catch(
        this.handleError
      ); // (error:any) => Observable.throw(error.json().error || 'Server error'));

  }

  // private handleErrorPromise (error: Response | any) {
  // console.error(error.message || error);
  // return Promise.reject(error.message || error);
  //   }



  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }

}
