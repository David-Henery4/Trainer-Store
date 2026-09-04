<script lang="ts">
    let { product } = $props();

    
</script>

<div class="mb-12">
    <a href={`/products/${product.id}`}>
      <h1>
        {product.name}
      </h1>
    </a>
  </div>