import {Category} from "./category"

export class Product {
  id: number;
  name: string;
  price: number;
  stock: number;
  description: string;
  imageName: string;
  categories: Category[];
  unity: string;
  pas: number;
  placement: number;
  qte: number;
}
