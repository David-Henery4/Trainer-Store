import { error, isHttpError } from '@sveltejs/kit';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch }) => {
	const controller = new AbortController();
	const timeout = setTimeout(() => {
		controller.abort();
	}, 5000);

	try {
		const response = await fetch('http://localhost:8000/api/products', {
			signal: controller.signal,
			headers: {
				Accept: 'application/json'
			}
		});

		if (!response.ok) {
			error(response.status, 'The API returned an error.');
		}

		let products: unknown;

		try {
			products = await response.json();
		} catch {
			error(502, 'The API returned invalid JSON.');
		}

		if (!Array.isArray(products)) {
			error(502, 'The API has returned an unexpected response.');
		}

		return {
			products
		};
	} catch (err) {
		if (isHttpError(err)) {
			throw err;
		}

		if (err instanceof DOMException && err.name === 'AbortError') {
			error(504, 'The server took too long to respond');
		}

		error(503, 'Unable to connect to the API.');
	} finally {
		clearTimeout(timeout);
	}
};
