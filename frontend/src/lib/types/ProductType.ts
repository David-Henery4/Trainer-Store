
export interface Product {
	id: number;
	name: string;
	brand: string;
	slug: string;
	description: string;
	price: string;
	image_url: string | null;
	is_active: boolean;
  created_at: string;
  updated_at: string;
	category_id: number;
}