import { Component, Pipe, PipeTransform } from '@angular/core';


@Pipe({
  name: 'filterUnique',
  pure: false
})
export class UniqueValuesPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    
    let ids = []
  	let uniqueArray = []

  	for (let i = 0; i < value.length; i++) {
  		if(ids.indexOf(value[i].id) == -1) {
  			ids.push(value[i].id)
  			uniqueArray.push(value[i])
  		}
  	}
  	
    return uniqueArray;
  }

}