
<script lang="ts">
  let {data} = $props()
</script>

{#if data.products.length <= 0}
  <div>
    There are no products.
  </div>
  {:else}
  {#each data.products as product}
  <div class="mb-12">
    <a href={`/products/${product.id}`}>
      <h1>
        {product.name}
      </h1>
    </a>
  </div>
{/each}
{/if}

