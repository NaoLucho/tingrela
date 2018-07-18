import { Component, OnInit } from '@angular/core'

import { ActivatedRoute, Params } from '@angular/router'

import { DataService } from './../../../services/data.service'
import { BasketService } from './../../../services/basket.service'

import { Product } from './../../../objects/product'

import { fadeInAnimation } from './../../../animations/routerFader.component'
import { GlobalsService } from './../../../services/globals.service'

@Component ({
  selector: 'app-productList',
  templateUrl: 'productDetails.component.html',
  animations: [fadeInAnimation],
  host: { '[@fadeInAnimation]': '' }
})

export class ProductDetailsComponent implements OnInit {
  private product: Product;
  private searchedProductID;
  private serverUrl: string;

  constructor(
    private activatedRoute: ActivatedRoute,
    private dataService: DataService,
    private basketService: BasketService,
    private globalsService: GlobalsService
  ) {
    this.serverUrl = globalsService.getAssets();
  }

  ngOnInit() {
    this.activatedRoute.params.subscribe((params: Params) => {
        this.searchedProductID = parseInt(params['id'], 10);
      }, err => console.log(err));

    this.dataService.getDataSubscribed().subscribe(() => {
      this.product = this.dataService.getProduct(this.searchedProductID)
    })

    this.dataService.initData()
  }

	// addProduct(productId, qte) {
  //     this.basketService.addProductBasket(productId,qte);
	// }

  addQte(product) {
    if (!product.qte) {
      product.qte = 0
    }
    product.qte = product.qte + product.step
    this.basketService.addProductBasket(product.id, product.qte);
  }

  deleteQte(product) {
    product.qte = product.qte - product.step
    this.basketService.addProductBasket(product.id, product.qte);
  }


}
