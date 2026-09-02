
<script lang="ts">
  let {data} = $props()
</script>

{#each data.products as product}
  <div class="mb-12">
    <a href={`/products/${product.id}`}>
      <h1>
        {product.name}
      </h1>
    </a>
  </div>
{/each}