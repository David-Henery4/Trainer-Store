<script lang="ts">
  let { data } = $props();

</script>

<a href="/admin/products">Back</a>

<h1>Edit Product</h1>

<form>
  <div>
    <label for="">Product Name</label>
    <input type="text" value={data.product.name}>
  </div>
</form>

