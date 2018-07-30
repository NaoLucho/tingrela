import { BrowserModule } from '@angular/platform-browser'
import { NgModule } from '@angular/core'
import { HttpModule, Http, RequestOptions } from '@angular/http'
import { FormsModule, ReactiveFormsModule } from '@angular/forms'
import { BrowserAnimationsModule } from '@angular/platform-browser/animations'


// Import des bibliothèques annexes

import { AgmCoreModule } from '@agm/core'
// import { ? } from vicopo

// Import des pages

import { AppComponent } from './app.component'
import { HomeComponent } from './components/home/home.component'
import { ShopComponent } from './components/shop/shop.component'
import { ProfileComponent} from './components/profile/profile.component'
import { ContactComponent} from './components/contact/contact.component'
import { BasketComponent} from './components/basket/basket.component'
import { CommandeComponent} from './components/commande/commande.component'

import { PaymentComponent} from './components/payment/payment.component'

// Import des composants

import { PageNotFound } from './components/pageNotFound/page_not_found.component'
import { AppRoutes } from './app.routes'
import { HeaderComponent } from './components/header/header.component'
import { FooterComponent } from './components/footer/footer.component'

import { PostComponent } from './post/post.component'


import { TypeBlockComponent } from './components/home/typeBlock/typeBlock.component'
import { CategoryListComponent } from './components/shop/categoryList/categoryList.component'
import { ProductListComponent } from './components/shop/productList/productList.component'
import { ProductDetailsComponent } from './components/shop/productDetails/productDetails.component'
import { CreationsComponent } from './components/creations/creations.component'


// Import des services

import { GlobalsService } from './services/globals.service'
import { AuthHttp, AuthConfig } from 'angular2-jwt';
import { DataService } from './services/data.service';
import { PaymentService } from './services/payment.service';
import { BasketService } from './services/basket.service';
import { CreationService } from './services/creation.service';
import { PostRepository } from './post/post-repository.service';

// Import security
import { AuthGuard } from './_guard/index';
import { AuthenticationComponent } from './authentication/authentication.component';
import { AuthenticationService } from './authentication/authentication.service';

// Import admin components
import { AdminHomeComponent} from './admin/home/home.component'

export function authHttpServiceFactory(http: Http, options: RequestOptions) {
    return new AuthHttp( new AuthConfig({}), http, options);
}

// Import des filtres et transformeurs (pipes)

import { CapitalizePipe } from './pipes/capitalize.pipe'
import { UniqueValuesPipe } from './pipes/uniqueValues.pipe'

import { environment } from './../environments/environment';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    ShopComponent,
    ProfileComponent,
    ContactComponent,
    BasketComponent,
    HeaderComponent,
    FooterComponent,
    TypeBlockComponent,
    CategoryListComponent,
    ProductListComponent,
    ProductDetailsComponent,
    CreationsComponent,
    CommandeComponent,
    PaymentComponent,
    AuthenticationComponent,
    PostComponent,
    AdminHomeComponent,
    PageNotFound,

    CapitalizePipe,
    UniqueValuesPipe
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutes,
    HttpModule,
    AgmCoreModule.forRoot({
      apiKey: environment.googleapikey
    }),
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [
    GlobalsService,
    {
      provide: AuthHttp,
      useFactory: authHttpServiceFactory,
      deps: [ Http, RequestOptions ]
    },
    DataService,
    AuthGuard,
    AuthenticationService,
    PostRepository,
    PaymentService,
    BasketService,
    CreationService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
