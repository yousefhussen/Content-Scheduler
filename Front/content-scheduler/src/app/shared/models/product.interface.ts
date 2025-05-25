import { ProductAttribute } from "./product-attribute.interface";

export interface Product {
  inStock: boolean;
  id: string;
  name: string;
  brand: string;
  price: number;
  image: string;
  totalRating: number;
  attributes: ProductAttribute[];
}