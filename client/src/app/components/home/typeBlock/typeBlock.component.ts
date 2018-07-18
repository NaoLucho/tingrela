import { Component, Input, OnInit, OnChanges } from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Fader } from './../../../animations/fader.animation'


@Component({
	selector:'my-nav-tree',
	templateUrl: 'typeBlock.component.html',
	styleUrls: ['typeBlock.style.scss'],
	animations: [
		Fader("200ms")
	]
})

export class TypeBlockComponent implements OnInit {
	private type: string;
	private hoveredType: boolean;
	private cache: boolean = false;
	private visibility = "false"
	@Input('parentType') parentType: string;

	constructor(private route: ActivatedRoute) {}

	ngOnInit() {
		this.route.params.subscribe((params: any) => {
        this.type = params["type"];
        this.visibility = "true";
      }, err => console.log(err));
	}
}
