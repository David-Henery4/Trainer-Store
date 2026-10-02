import type { Product } from '$lib/types/ProductType.js';
import type { Categories } from '$lib/types/CategoriesType.js';

export async function load({ fetch, params }) {
	const productInfo = fetch(`http://localhost:8000/api/products/${params.id}`);
	const categoriesInfo = fetch(`http://127.0.0.1:8000/api/category`);

	const [productResponse, categoriesResponse] = await Promise.all([productInfo, categoriesInfo]);

	// const response = await fetch(`http://localhost:8000/api/products/${params.id}`);

	if (!productResponse.ok) {
		const error = await productResponse.json();
		console.error(error);
		throw new Error(error.error ?? 'Something went wrong');
	}
	if (!categoriesResponse.ok) {
		const error = await categoriesResponse.json();
		console.error(error);
		throw new Error(error.error ?? 'Something went wrong');
	}

	if (categoriesResponse.status === 422) {
		const data = await categoriesResponse.json();
		console.log(data.errors);
		return { errors: data.errors };
	}
	if (productResponse.status === 422) {
		const data = await productResponse.json();
		console.log(data.errors);
		return { errors: data.errors };
	}

	const [product, categories]: [Product, Categories[]] = await Promise.all([
		productResponse.json(),
		categoriesResponse.json()
	]);

  console.log(product)

	return {
		product,
		categories
	};
}
