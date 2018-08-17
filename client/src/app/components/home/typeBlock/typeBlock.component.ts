import { Component, Input, OnInit, OnChanges } from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Fader } from './../../../animations/fader.animation'
import { Type } from './../../../objects/type'


@Component({
    selector: 'app-nav-tree',
    templateUrl: 'typeBlock.component.html',
    styleUrls: ['typeBlock.style.scss'],
    animations: [
        Fader('200ms')
    ]
})

export class TypeBlockComponent implements OnInit {
    public type: Type[];
    public cache = 'false';
    public visibility = 'false'
    @Input('parentType') parentType: any;

    constructor(private route: ActivatedRoute) {}

    ngOnInit() {
        this.route.params.subscribe((params: any) => {
        this.type = params['type'];
        this.visibility = 'true';
      }, err => console.log(err));
    }
}
