<script lang="ts">
	let { data } = $props();
	import Input from '$lib/components/Input.svelte';
</script>

<main class="py-8">
	<div class="my-8 flex gap-8">
		<a href="/admin/products">Back to products</a>
		<a href="/admin">Back to Dashboard</a>
	</div>

	<h1 class="mb-8 text-xl">Create new product</h1>

	<form class="w-full max-w-80 border p-4">
		<Input id={'name'} label={'name'} name={'name'} type={'text'} autocomplete="name" />

		<!-- Category -->
		<div>
			<label for="category">Category</label>
			<select name="category" id="category">
				{#each data.categories as category}
					<option value={category.id}>{category.name}</option>
				{/each}
			</select>
		</div>
		<!--  -->

		<Input id={'brand'} label={'brand'} name={'brand'} type={'text'} />
		<Input id={'slug'} label={'slug'} name={'slug'} type={'text'} />
		<Input id={'price'} label={'price'} name={'price'} type={'text'} />
		<Input
			id={'activeProduct'}
			label={'Is a active product?'}
			name={'activeProduct'}
			type={'checkbox'}
		/>
		<Input
			id={'description'}
			label={'description'}
			name={'description'}
			type={'off'}
			isTextarea={true}
		/>
		<button class="border px-4 py-2 hover:cursor-pointer"> Submit </button>
	</form>
</main>
