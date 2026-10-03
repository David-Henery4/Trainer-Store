<script lang="ts">
	import Input from '$lib/components/Input.svelte';
	let { data } = $props();
	const product = () => {
		if (data.product) {
			return { ...data.product };
		}
		return {
			id: null,
			name: '',
			brand: '',
			slug: '',
			description: '',
			price: '',
			imageUrl: '',
			isActive: false,
			created_at: '',
			updated_at: '',
			category: {
        id: null,
        name: "",
        slug: ""
      }
		};
	};
	const categories = () => {
		if (data.categories) {
			return [...data.categories];
		}
		return [];
	};
	let isEdit = $state(false);
	const handleEditChange = () => (!isEdit ? (isEdit = true) : (isEdit = false));
	$effect(() => {
		console.log('Edit Status: ', isEdit);
	});
  let selectedCategoryId = $state(product().category.id);
</script>

<a href="/admin/products">Back</a>

<h1>{product().name} Product</h1>

<div>
	<button onclick={handleEditChange} class="my-6 border px-4 py-2 hover:cursor-pointer">
		Edit
	</button>
	<button class="my-6 border px-4 py-2 hover:cursor-pointer"> Save </button>
	<button class="my-6 border px-4 py-2 hover:cursor-pointer"> Cancel </button>
</div>

<form class={`w-full max-w-80 border p-4 ${!isEdit && 'opacity-50'}`}>
	<fieldset disabled={!isEdit}>
		<Input
			id={'name'}
			label={'name'}
			name={'name'}
			type="text"
			autocomplete="name"
			value={product().name}
		/>
		<!-- Need to fetch the category with the product -->
		<!-- <Input id={'category'} label={'category'} name={'category'} type={'text'} /> -->
		<div>
			<label for="category">Category</label>
			<select bind:value={selectedCategoryId} required name="category" id="category">
				{#each categories() as category}
					<option
						value={category.id}>{category.name}</option
					>
				{/each}
				<!-- Loop through and find the current product category and select that as default (Add "selected" to the option element.) -->
			</select>
		</div>
		<!--**************************************-->
		<Input id={'brand'} label={'brand'} name={'brand'} type={'text'} value={product().brand} />
		<Input id={'slug'} label={'slug'} name={'slug'} type={'text'} value={product().slug} />
		<Input id={'price'} label={'price'} name={'price'} type={'text'} value={product().price} />
		<Input
			id={'activeProduct'}
			label={'Is a active product?'}
			name={'activeProduct'}
			type={'checkbox'}
			value={product().isActive}
      checked={product().isActive}
		/>
		<Input
			id={'description'}
			label={'description'}
			name={'description'}
			type={'text'}
			isTextarea={true}
			value={product().description}
		/>
	</fieldset>
</form>
