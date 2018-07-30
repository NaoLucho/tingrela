import { Component, Input, OnInit, OnChanges, SimpleChanges, Renderer2 } from '@angular/core'
import { ActivatedRoute, Params } from '@angular/router'
import { Fader } from './../../../animations/fader.animation'
import { TranslaterX } from './../../../animations/translateX.animation'

import { DataService } from './../../../services/data.service'
import { BasketService } from './../../../services/basket.service'
import { GlobalsService } from './../../../services/globals.service'


@Component({
  selector: 'app-product-list',
  templateUrl: 'productList.component.html',
  animations: [
    Fader(),
    TranslaterX()
  ]
})

export class ProductListComponent implements OnChanges, OnInit {
  @Input() data: any
  protected products: any
  private type: string
  private category: string
  private overlay: number
  private visibility = []
  private loader = 'true'
  private nbImages = 0;
  private imagesLoaded = false;
  private serverUrl: string;
  private tva;

  protected qteListe: number[] = [];

  constructor(
    private activatedRoute: ActivatedRoute,
    private dataService: DataService,
    private basketService: BasketService,
    private globalsService: GlobalsService
  ) {
    this.serverUrl = globalsService.getAssets();
    this.getTva();
  }


  ngOnChanges(changes: SimpleChanges) {
    if (this.data) {
      this.products = this.getProducts(this.data, this.type)

      // Get basket
      const basket = this.basketService.getBasket();
      /* console.log('basket');
      console.log(basket) */
      if (basket) {
        basket.forEach(basketItem => {
          this.products.forEach(product => {
              if (basketItem.id === product.id) {
                product.qte = basketItem.qte
              }
          });
        });
      }
      // Assign product qte from basket

      this.loader = 'false';
    }
  }

  ngOnInit() {
    this.activatedRoute.params.subscribe((params: Params) => {
        this.type = params['type'];
        this.category = undefined;
        this.products = this.getProducts(this.data, this.type);
      }, err => console.log(err));

    this.dataService.getCategorySubscribed().subscribe(category => {
        this.category = category;
        this.products = this.getProducts(this.data, this.type);
      }, err => console.log(err));
  }

  getTva() {
    this.basketService.getTva().subscribe(data => {
      // console.log("getTva in comp "+data)
      if (data) {
        this.tva = data;
      }
    }, error => {
      // Log errors if any
      alert('Il y a eu une erreur. Réferrez vous à l\'administrateur')
    }, () => {
     /*  this.getBasket() */
    });
  }

  getProducts(data, selectedType) {
    let categories = []
    let products = []
    if (data) {
      data.forEach((dataelement) => {
        // On cherche le type sélectionné

        if (dataelement.type === selectedType) {
          // On parcourt les categories pour ajouter tous les produits associés
          categories = dataelement.categories
          categories.forEach((catelement) => {
            // On cherche si la personne a cliqué sur une categorie particulière pour filtrer les produits
            if (this.category) {
              if (this.category === catelement.category) {
                // On réinitialise les produits si jamais il y en a qui avaient été ajoutés.
                products = catelement.products
                return products
              }
              // Sinon on ajoute tous les produits voulus
            } else {
              products = products.concat(catelement.products)
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
    product.qte = product.qte + product.step
    this.basketService.addProductBasket(product.id, product.qte);
  }

  deleteQte(product) {
    product.qte = product.qte - product.step
    this.basketService.addProductBasket(product.id, product.qte);
  }
}
