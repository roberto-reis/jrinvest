
export const formatMoneyBr = (value, maxFractionDigits = 2) => {
	return new Intl.NumberFormat('pt-BR', {
			style: 'currency',
			currency: 'BRL',
			minimumFractionDigits: 2,
			maximumFractionDigits: maxFractionDigits,
	}).format(value);
}

export const numberFormatterBr = (value, maxFractionDigits = 2) => {
	return new Intl.NumberFormat('pt-BR', {
			style: 'decimal',
			currency: 'BRL',
			minimumFractionDigits: 2,
			maximumFractionDigits: maxFractionDigits,
	}).format(value);
}

export const formatDateBr = (date) => {
	let data = new Date(date);
	return data.toLocaleDateString('pt-BR', {
			day: '2-digit',
			month: '2-digit',
			year: 'numeric',
			timeZone: 'UTC',
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