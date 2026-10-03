<script lang="ts">
	import type { HTMLInputAttributes } from 'svelte/elements';
	interface Props {
		label: string;
		id: string;
		name: string;
		type: 'text' | 'number' | 'checkbox'| 'off';
		autocomplete?: HTMLInputAttributes['autocomplete'];
		isTextarea?: boolean;
    isEdit?: boolean;
    value?: string | boolean;
    checked?: boolean;
	}
	const { label, id, name, type, autocomplete = 'off', isTextarea = false, isEdit = false, value= "", checked = false }: Props = $props();
</script>

<!--  I WAS HERE IMPLEMENTING THE "ISACTIVE" DEFAULT STATE! -->

<div>
	<label for={id} class="capitalize">{label}</label>
	{#if isTextarea}
		<textarea class="resize-none w-full min-h-40" name={name} id={id}></textarea>
    {:else if type === "checkbox"}
		<input id={id} name={name} autocomplete={autocomplete} class="w-4.5 h-4.5" type={type} value={value} checked={checked}  />
	{:else}
		<input id={id} name={name} autocomplete={autocomplete} class="w-full" type={type} value={value}  />
	{/if}
</div>
