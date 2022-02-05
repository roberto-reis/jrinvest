
export const formatMoneyBr = (value) => {
	return new Intl.NumberFormat('pt-BR', {
			style: 'currency',
			currency: 'BRL',
			maximumFractionDigits: 3
	}).format(value);
}

export const formatDateBr = (date) => {
	let data = new Date(date);
	return data.toLocaleDateString('pt-BR', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric'
	});
}

export const setUrlParamr = (key, value) => {
	let paramr = new URLSearchParams(window.location.search);
	paramr.set(key, value);
	return paramr;
}

export const getUrlParamr = (key) => {
	let paramr = new URLSearchParams(window.location.search);
	return paramr.get(key);
}