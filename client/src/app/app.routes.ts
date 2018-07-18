import { NgModule } from '@angular/core'
import { RouterModule } from '@angular/router'

import { PageNotFound } from './components/pageNotFound/page_not_found.component'
import { HomeComponent } from './components/home/home.component'
import { ShopComponent } from './components/shop/shop.component'
import { ProfileComponent} from './components/profile/profile.component'
import { ContactComponent} from './components/contact/contact.component'
import { BasketComponent} from './components/basket/basket.component'
import { CreationsComponent } from './components/creations/creations.component'
import { ProductDetailsComponent } from './components/shop/productDetails/productDetails.component'
import { CommandeComponent} from './components/commande/commande.component'
import { PaymentComponent} from './components/payment/payment.component'

import { AuthenticationComponent } from './authentication/authentication.component'
import { PostComponent } from './post/post.component'
import { AuthGuard } from './_guard/index'



import { AdminHomeComponent} from './admin/home/home.component'


@NgModule({
	imports: [
		RouterModule.forRoot([{
			path:'accueil',
			component: HomeComponent,
		},{
			path:'shop/:type',
			children: [{
				path:'',
				component: ShopComponent
			},{
				path: ':id',
				component: ProductDetailsComponent
			}]
		},{
			path: 'profil',
			component: ProfileComponent,

			//canActivate: [AuthGuard]
		},{
			path: 'creations',
			component: CreationsComponent
		},{
			path: 'contact',
			component: ContactComponent
		},{
			path: 'panier',
			component: BasketComponent
		},{
      path: 'commande',
      component: CommandeComponent
    },{
      path: 'payment',
      component: PaymentComponent
    },{
        path: 'login',
        component: AuthenticationComponent
    },{
        path: 'posts',
        component: PostComponent,
        canActivate: [AuthGuard]
    },{
        path: 'admin',
        component: AdminHomeComponent,
        canActivate: [AuthGuard]
    },{
			path: '',
			redirectTo: '/accueil',
			pathMatch: 'full'
		},{
			path: '**',
			component: PageNotFound
		}])
	],
	exports: [
		RouterModule
	]
})

export class AppRoutes {}
