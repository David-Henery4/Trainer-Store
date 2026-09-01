
<script lang="ts">
  let {data} = $props()
</script>

{#each data.products as product}
  <div>
    <h1>
      {product.name}
    </h1>
  </div>
{/each}