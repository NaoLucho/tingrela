import { Component, Input, OnInit, OnChanges, SimpleChanges, Renderer2 } from '@angular/core'
import { ActivatedRoute, Params } from '@angular/router'
import { Fader } from './../../../animations/fader.animation'
import { TranslaterX } from './../../../animations/translateX.animation'

import { DataService } from './../../../services/data.service'
import { BasketService } from './../../../services/basket.service'


@Component({
  selector: 'my-product-list',
  templateUrl: 'productList.component.html',
  animations: [
    Fader(),
    TranslaterX()
  ]
})

export class ProductListComponent {
  @Input() data: any
  protected products: any
  private type: string
  private category: string
  private overlay: number
  private visibility = []
  private loader = "true"
  private nbImages: number = 0;
  private imagesLoaded: boolean = false;

  protected qteListe: number[] = [];

  constructor(
    private activatedRoute: ActivatedRoute,
    private dataService: DataService,
    private basketService: BasketService
  ) { }


  ngOnChanges(changes: SimpleChanges) {
    if (this.data) {
      this.products = this.getProducts(this.data, this.type)

      //Get basket
      let basket = this.basketService.getBasket();
      /* console.log('basket');
      console.log(basket) */
      if(basket)
        basket.forEach(basketItem => {
          this.products.forEach(product => {
              if(basketItem.id == product.id)
                product.qte = basketItem.qte
          });
        });

      //Assign product qte from basket

      this.loader = "false"
    }
  }

  ngOnInit() {
    this.activatedRoute.params.subscribe((params: Params) => {
        this.type = params["type"];
        this.category = undefined;
        this.products = this.getProducts(this.data, this.type);
      }, err => console.log(err));

    this.dataService.getCategorySubscribed().subscribe(category => {
        this.category = category;
        this.products = this.getProducts(this.data, this.type);
      }, err => console.log(err));

  }

  getProducts(data, selectedType) {
    let categories = []
    let products = []
    if (data) {
      data.forEach((e) => {
        //On cherche le type sélectionné

        if (e.type === selectedType) {
          //On parcourt les categories pour ajouter tous les produits associés
          categories = e.categories
          categories.forEach((e) => {
            //On cherche si la personne a cliqué sur une categorie particulière pour filtrer les produits
            if (this.category) {
              if (this.category === e.category) {
                //On réinitialise les produits si jamais il y en a qui avaient été ajoutés.
                products = e.products
                return products
              }
              //Sinon on ajoute tous les produits voulus
            } else {
              products = products.concat(e.products)
            }
          })
        }
      })
    }
    return products
  }



  addQte(product) {
    if (!product.qte) {
      product.qte = 0
    }
    product.qte = product.qte + product.pas
/*     console.log("qte of produitid:" + product);
    console.log(product.qte); */
    this.basketService.addProductBasket(product.id, product.qte);
  }

  deleteQte(product) {
    product.qte = product.qte - product.pas
    /* console.log("qte of produitid:" + product);
    console.log(product.qte); */
    this.basketService.addProductBasket(product.id, product.qte);
  }
}
