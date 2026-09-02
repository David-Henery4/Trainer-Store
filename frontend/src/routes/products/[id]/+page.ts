
export async function load ({fetch, params}){
  const response = await fetch(`http://localhost:8000/api/products/${params.id}`);
  const product = await response.json()
  return {
    product
  }
}

