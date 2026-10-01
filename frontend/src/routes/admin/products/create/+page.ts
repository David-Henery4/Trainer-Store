import type { Categories } from '$lib/types/CategoriesType.js';

export async function load({ fetch }) {
  const response = await fetch(`http://127.0.0.1:8000/api/category`);

  if (!response.ok) {
    const error = await response.json();
    console.error(error);
    throw new Error(error.error ?? 'Something went wrong');
  }

  if (response.status === 422) {
    const data = await response.json();

    console.log(data.errors);
    return { errors: data.errors };
  }

  const categories: Categories[] = await response.json();

  return {categories}
}
