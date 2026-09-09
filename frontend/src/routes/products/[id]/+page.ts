
export async function load ({fetch, params}){
  const response = await fetch(`http://localhost:8000/api/products/${params.id}`);


  if (!response.ok){
    const error = await response.json()
    console.error(error);
    throw new Error(error.error ?? "Something went wrong")
  }

  if (response.status === 422) {
		const data = await response.json();

		console.log(data.errors);
    return {errors: data.errors}
	}

  const product = await response.json()

  return {
    product
  }
}

