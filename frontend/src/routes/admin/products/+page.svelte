<script lang="ts">
  // import ProductCard from "$lib/components/ProductCard.svelte";
  let {data} = $props()
</script>

<main class="mt-8 flex flex-col gap-8">

  <a href="/admin">Back to Dashboard</a>
  
  <h1>Products</h1>

  <a href="products/create">
    Add new product +
  </a>
  
  <div class="mt-8">
    
  {#if data.products.length <= 0}
  <div>
    There are no products.
  </div>
  {:else}
  {#each data.products as product}
  <div class="mb-12">
    <a href={`products/${product.id}`}>
      <h1>
        {product.name}
      </h1>
    </a>
  </div>
  {/each}
  {/if}
</div>
</main>