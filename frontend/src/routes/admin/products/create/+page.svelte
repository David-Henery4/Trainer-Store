<script lang="ts">
	import Input from '$lib/components/Input.svelte';
</script>

<h1>Create new product</h1>

<form class="w-full max-w-80 border p-4">
	<Input id={'name'} label={'name'} name={'name'} type={'text'} autocomplete="name" />
	<Input id={'category'} label={'category'} name={'category'} type={'text'} />
	<Input id={'brand'} label={'brand'} name={'brand'} type={'text'} />
	<Input id={'slug'} label={'slug'} name={'slug'} type={'text'} />
	<Input id={'price'} label={'price'} name={'price'} type={'text'} />
	<Input id={'activeProduct'} label={'Is a active product?'} name={'activeProduct'} type={'checkbox'} />
	<Input id={'description'} label={'description'} name={'description'} type={'off'} isTextarea={true} />
</form>
