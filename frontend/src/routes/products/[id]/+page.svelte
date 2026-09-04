<script lang="ts">
	//
	let { data } = $props();

	// Temp cart logic (Won't even be located here, just temp for testing)

	// Cart Types
	type CartItem = {
		productId: number;
		quantity: number;
		price: number;
		name: string;
		total: number;
	};
	type CartState = {
		items: CartItem[];
		isOpen: boolean;
	};

	// Cart state
	let cart = $state<CartState>({
		isOpen: false,
		items: []
	});

	const handleAddToCart = () => {
		const activeItem = cart.items.find((item: CartItem) => item.productId === data.product.id);

		if (activeItem) {
			cart.items = cart.items.map((item) => {
				if (item.productId === data.product.id) {
					item.quantity += 1;
					item.total = item.quantity * item.price;
				}
				return item;
			});
			return;
		}
		cart.items = [
			...cart.items,
			{
				productId: data.product.id,
				quantity: 1,
				price: Number(data.product.price),
				name: data.product.name,
				total: Number(data.product.price)
			}
		];
	};

	const handleQuantityChange = (itemId: number, changeType: 'INC' | 'DEC') => {
    cart.items = cart.items.map((item) => {
      if (item.productId !== itemId) return item;

      if (changeType === 'DEC') {
        item.quantity -= 1
        return item
      }

      item.quantity += 1
      return item

    }).filter((item) => item.quantity > 0 )

	};

  const handleRemoveItem = (itemId: number) => {
    cart.items = cart.items.filter((item) => itemId !== item.productId)
  }

  const handleClearCart = () => {
    cart.items = []
  }

	// $derived || $derived.by's job is to read state and calculate a value.
	// NOT to modify or interact the state it depends on.
	// $derived gives me a value that stays in sync with the reactive state it depends on, but it doesn’t change that state itself.
	let subtotal = $derived.by(() => {
		let itemsListTotal = 0;
		cart.items.forEach((item) => {
			itemsListTotal += item.total;
		});
		return itemsListTotal;
	});

	let totalItemsQuantity = $derived.by(() => {
		let totalItems = 0;
		cart.items.forEach((item) => {
			totalItems += item.quantity;
		});
		return totalItems;
	});

	//
</script>

<h1>{data.product.name}</h1>

<p>{data.product.description}</p>

<div>
	<button class="hover:cursor-pointer" onclick={() => handleAddToCart()}> Add to cart </button>
</div>

<!-- Temp cart -->

<div class="mt-24">
	<p>
		🛒 <span>Total Items: {totalItemsQuantity}</span>
	</p>
	{#if cart.items.length <= 0}
		<p>You have no items</p>
	{:else}
		<ul>
			{#each cart.items as cartItem}
				<li>
					<p>Item name: <span>{cartItem.name}</span></p>
					<p>Item quantity: <span>{cartItem.quantity}</span></p>
					<div>
						<button onclick={() => handleQuantityChange(cartItem.productId, "DEC")}  class="bg-blue-600 p-2 hover:cursor-pointer">
							Decrease quantitiy <span>➖</span>
						</button>
            <button onclick={() => handleRemoveItem(cartItem.productId)} class="bg-red-500 p-2 hover:cursor-pointer">
              Remove Item
            </button>
						<button onclick={() => handleQuantityChange(cartItem.productId, "INC")} class="bg-blue-600 p-2 hover:cursor-pointer">
							Increase quantitiy
							<span>➕</span>
						</button>
					</div>
					<p>Item price: <span>{cartItem.price}</span></p>
					<p>Item total: <span>{cartItem.total}</span></p>
					<button class="bg-red-500 p-2 hover:cursor-pointer">Remove Item</button>
				</li>
			{/each}
		</ul>
		<div class="mt-4">
			<p>subtotal: <span>{subtotal}</span></p>
			<button onclick={handleClearCart} class="bg-red-500 p-2 hover:cursor-pointer">Clear Cart</button>
		</div>
	{/if}
</div>
