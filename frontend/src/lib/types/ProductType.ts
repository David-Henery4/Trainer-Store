
export interface Product {
	id: number;
	name: string;
	brand: string;
	slug: string;
	description: string;
	price: string;
	imageUrl: string | null;
	isActive: boolean;
	created_at: string;
	updated_at: string;
	category: { id: number; name: string; slug: string };
}