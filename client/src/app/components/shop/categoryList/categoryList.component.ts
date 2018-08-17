import { Component, Input, OnInit, OnChanges, SimpleChanges } from '@angular/core'
import { ActivatedRoute, Params } from '@angular/router'
import { TranslaterY } from './../../../animations/translateY.animation'
import { FadeIn } from './../../../animations/fadeIn.animation'

import { DataService } from './../../../services/data.service'

import { Type } from './../../../objects/type'

@Component ({
    selector: 'app-category-list',
    templateUrl: 'categoryList.component.html',
    animations: [
        TranslaterY(),
        FadeIn()
    ]
})

export class CategoryListComponent implements OnChanges {

    @Input() data: any
    public categories: any
    private type: string
    public filteredCategory: number
    private anim: string;


    constructor(
        private activatedRoute: ActivatedRoute,
        private dataService: DataService
    ) {
        this.activatedRoute.params.subscribe((params: Params) => {
        this.type = params['type'];
        this.categories = this.getCategories(this.data, this.type);
        this.anim = 'notVoid';
      }, err => console.log(err));
    }

    ngOnChanges(changes: SimpleChanges) {
        if(this.data) {
            this.categories = this.getCategories(this.data, this.type);
        }
    }

    getCategories(data, selectedType) {
        let categories = []

        if(data) {
            data.forEach((e) => {
                if (e.type === selectedType) {
                    categories = e.categories
                }
            })
        }

        return categories
    }

    filterCategory(category, id) {
        this.dataService.sendCategory(category);

        // application du style pour le focus
        this.filteredCategory = id;
    }

}
