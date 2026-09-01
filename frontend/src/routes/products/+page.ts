
export async function load({fetch}) {
  const response = await fetch('http://127.0.0.1:8000/api/products')
  const products = await response.json()
  return {products}
}