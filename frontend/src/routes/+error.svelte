<script lang="ts">
	import {page} from '$app/state';
</script>

<main class="error-page">
<h1>Something went wrong!😲</h1>
	<p>
    {page.error?.message}
  </p>

  <button onclick={() => window.location.reload()}>
  Try again
</button>
</main>
