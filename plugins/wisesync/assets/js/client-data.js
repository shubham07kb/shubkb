function getIPVersion(ipAddress) {
	const ipv4Regex =
		/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
	const ipv6Regex = /^([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}$/;

	// Check if the IP address matches either regex
	if (ipv4Regex.test(ipAddress)) {
		return 4;
	} else if (ipv6Regex.test(ipAddress)) {
		return 6;
	}
	return undefined; // Or you could throw an error here for invalid IPs
}

function addIPData([ip1, ip2, ip3]) {
	const finalIP = { ipv4: null, ipv6: null };
	for (const ip of [ip1, ip2, ip3]) {
		if (getIPVersion(ip) === 4 && !finalIP.ipv4) {
			finalIP.ipv4 = ip;
		} else if (getIPVersion(ip) === 6 && !finalIP.ipv6) {
			finalIP.ipv6 = ip;
		}
	}
	return finalIP;
}

function isLessThanOne(valueCheck, array) {
	let p = 0;
	array.forEach((invalue) => {
		if (Math.abs(invalue - valueCheck) < 1) {
			p = 1;
		}
	});
	if (p === 0) {
		return false;
	}
	return true;
}
function averaged(array) {
	let sum = 0;
	array.forEach((value) => {
		sum += parseFloat(value);
	});
	return (sum / array.length).toFixed(3);
}

function makeNearAverage(arrayToAverage) {
	const grouped = { 0: [arrayToAverage[0]] };
	arrayToAverage.forEach((value) => {
		if (value !== arrayToAverage[0]) {
			let p = 0;
			for (const property in grouped) {
				if (isLessThanOne(value, grouped[property])) {
					grouped[property].push(value);
					p = 1;
					break;
				}
			}
			if (p === 0) {
				grouped[Object.keys(grouped).length] = [value];
			}
		}
	});
	const finalArray = [];
	for (const property in grouped) {
		finalArray.push(averaged(grouped[property]));
	}
	return finalArray;
}

async function getCurrentData(ajaxUrl, nonce, action) {
	// Use Promise.all to fetch multiple URLs concurrently
	const [response1, response2, response3, response4] = await Promise.all([
		fetch('https://ipapi.co/json/'),
		fetch('https://api.ipapi.is/'),
		fetch('https://ipwho.is/'),
		navigator.userAgentData.getHighEntropyValues([
			'architecture',
			'bitness',
			'brands',
			'mobile',
			'model',
			'platform',
			'platformVersion',
			'uaFullVersion',
			'fullVersionList',
			'wow64',
		]),
	]);

	// Check if each request was successful
	if (!response1.ok || !response2.ok || !response3.ok) {
		throw new Error('One or more requests failed');
	}

	// Extract JSON data from each response
	const data1 = await response1.json();
	const data2 = await response2.json();
	const data3 = await response3.json();
	const data4 = await response4;

	const rirNames = {
		AFRINIC: 'African Network Information Center',
		APNIC: 'Asia-Pacific Network Information Centre',
		ARIN: 'American Registry for Internet Numbers',
		LACNIC: 'Latin American and Caribbean IP address Regional Registry',
		RIPE: 'Réseaux IP Européens Network Coordination Centre',
		'RIPE NCC': 'Réseaux IP Européens Network Coordination Centre',
	};

	// Create the final data object
	const finalData = {
		ip: addIPData([data1.ip, data2.ip, data3.ip]),
		data: {
			location: {
				city: [
					...new Set([data1.city, data2.location.city, data3.city]),
				], // data1.city is a string, data2.location.city is a string, data3.city is a string
				country_code: [
					...new Set([
						data1.country_code, // data1.country_code is a string
						data2.location.country_code, // data2.location.country_code is a string
						data3.country_code, // data3.country_code is a string
					]),
				],
				country_code_iso3: [data1.country_code_iso3], // data1.country_code_iso3 is a string
				country_name: [
					...new Set([
						data1.country_name, // data1.country_name is a string
						data2.location.country, // data2.location.country is a string
						data3.country, // data3.country is a string
					]),
				],
				country_capital: [
					...new Set([data1.country_capital, data3.capital]),
				], // data1.country_capital is a string, data3.capital is a string
				continent_code: [
					...new Set([
						data1.continent_code, // data1.continent_code is a string
						data2.location.continent, // data2.location.continent is a string
						data3.continent_code, // data3.continent_code is a string
					]),
				],
				continent_name: [
					...new Set([
						data1.timezone.split('/')[0], // data1.timezone is a string
						data2.location.timezone.split('/')[0], // data2.location.timezone is a string
						data3.continent, // data3.continent is a string
					]),
				],
				postal: [...new Set([data1.postal, data2.location.zip])], // data1.postal is a string, data2.location.zip is a string
				region: [...new Set([data1.region, data3.region])], // data1.region is a string, data3.region is a string
				region_code: [
					...new Set([data1.region_code, data3.region_code]),
				], // data1.region_code is a string, data3.region_code is a string
				latitude: makeNearAverage([
					...new Set([
						data1.latitude.toFixed(3), // data1.latitude is a string
						data2.location.latitude.toFixed(3), // data2.location.latitude is a string
						data3.latitude.toFixed(3), // data3.latitude is a string
					]),
				]),
				longitude: makeNearAverage([
					...new Set([
						data1.longitude.toFixed(3), // data1.longitude is a float
						data2.location.longitude.toFixed(3), // data2.location.longitude is a float
						data3.longitude.toFixed(3), // data3.longitude is a float
					]),
				]),
				flag_emoji: [data3.flag.emoji], // data3.flag.emoji is a string
				flag_emoji_unicode: data3.flag.emoji_unicode.split(' '), // data3.flag.emoji_unicode is a string
			},
			time: {
				timezone: [
					...new Set([
						data1.timezone, // data1.timezone is a string
						data2.location.timezone, // data2.location.timezone is a string
						data3.timezone.id, // data3.timezone.id is a string
					]),
				],
				utc_offset: [
					...new Set([
						`+${data1.utc_offset.slice(
							1,
							3
						)}:${data1.utc_offset.slice(3)}`, // data1.utc_offset is a string
						data2.location.local_time.slice(-6), // data2.location.local_time is a string
						data3.timezone.utc, // data3.timezone.utc is a string
					]),
				],
				abbr: [data3.timezone.abbr], // data3.timezone.abbr is a string
			},
			other: {
				country_calling_code: [
					...new Set([
						data1.country_calling_code,
						'+' + data3.calling_code,
					]), // data1.country_calling_code is a string, data3.calling_code is a string
				],
				currency: [data1.currency], // data1.currency is a string
				currency_name: [data1.currency_name], // data1.currency_name is a string
				languages: data1.languages.split(','), // data1.languages is a string
				country_area: [data1.country_area], // data1.country_area is a string
				country_population: [data1.country_population], // data1.country_population is a string
				datacenter:
					data2.asn.type === 'hosting' || data2.is_datacenter === true // data2.asn.type is a string, data2.is_datacenter is a boolean
						? data2.datacenter
						: null,
			},
			device: data4,
		},
		network: {
			asn: [
				...new Set([
					data1.asn.replace('AS', ''), // data1.asn is a string
					data2.asn.asn.toString(), // data2.asn.asn is a string
					data3.connection.asn.toString(), // data3.connection.asn is a string
				]),
			],
			isp: [...new Set([data1.org, data2.asn.org, data3.connection.org])], // data1.org is a string, data2.asn.org is a string, data3.connection.org is a string
			domian: [data3.connection.domain], // data3.connection.domain is a string
			type: [data2.asn.type], // data2.asn.type is a string
			rir: [data2.rir], // data2.rir is a string
			rir_name: [rirNames[data2.rir] || null],
			isMobile: data2.is_mobile, // data2.is_mobile is a boolean
			isVPN: data2.is_vpn, // data2.is_vpn is a boolean
			isProxy: data2.is_proxy, // data2.is_proxy is a boolean
			isBogon: data2.is_bogon, // data2.is_bogon is a boolean
			isCrawler: data2.is_crawler, // data2.is_crawler is a boolean
			isDatacenter: data2.is_datacenter, // data2.is_datacenter is a boolean | string
			isTor: data2.is_tor, // data2.is_tor is a boolean
			isAbuser: data2.is_abuser, // data2.is_abuser is a boolean
		},
	};
	const form = new FormData();
	form.append('nonce', nonce);
	// form.append('data', JSON.stringify(finalData));
	fetch(ajaxUrl + '?action=' + action, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: new URLSearchParams({
			nonce,
			data: JSON.stringify(finalData),
		}).toString(),
	});
}

// Call the function
// eslint-disable-next-line no-undef
if (typeof clientData.cip === 'object') {
	if (
		// eslint-disable-next-line no-undef
		!Object.values(clientData.cip).includes(clientData.ip) &&
		// eslint-disable-next-line no-undef
		clientData.ip !== 'localhost'
	) {
		// eslint-disable-next-line no-undef
		getCurrentData(clientData.ajaxUrl, clientData.nonce, clientData.action);
	}
}
