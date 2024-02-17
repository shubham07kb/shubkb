const elementsReg = document.querySelectorAll('.passkey_reg');
elementsReg.forEach(function (element) {
	element.addEventListener('click', doRegister);
});
function doRegister() {
	register.a();
}
const elementsVal = document.querySelectorAll('.passkey_val');
elementsVal.forEach(function (element) {
	element.addEventListener('click', doValidate);
});
function doValidate() {
	validate.a();
}
const helper = {
	atb: (b) => {
		const u = new Uint8Array(b);
		let s = '';
		for (let i = 0; i < u.byteLength; i++) {
			s += String.fromCharCode(u[i]);
		}
		return btoa(s);
	},
	bta: (o) => {
		const pre = '=?BINARY?B?',
			suf = '?=';
		for (const k in o) {
			if (typeof o[k] === 'string') {
				const s = o[k];
				if (
					s.substring(0, pre.length) === pre &&
					s.substring(s.length - suf.length) === suf
				) {
					const b = window.atob(
							s.substring(pre.length, s.length - suf.length)
						),
						u = new Uint8Array(b.length);
					for (let i = 0; i < b.length; i++) {
						u[i] = b.charCodeAt(i);
					}
					o[k] = u.buffer;
				}
			} else {
				helper.bta(o[k]);
			}
		}
	},
	ajax: (url, data, after) => {
		const form = new FormData();
		for (const [k, v] of Object.entries(data)) {
			form.append(k, v);
		}
		fetch(url, { method: 'POST', body: form })
			.then((res) => res.text())
			.then((res) => after(res));
	},
};

const register = {
	a: () =>
		helper.ajax(
			'/wp-admin/admin-ajax.php?action=passkey',
			{
				request_type: 'get_credential_json',
			},
			async (res) => {
				res = JSON.parse(res);
				helper.bta(res.data.credential);
				register.b(
					await navigator.credentials.create(res.data.credential)
				);
			}
		),
	b: (cred) =>
		helper.ajax(
			'/wp-admin/admin-ajax.php?action=passkey',
			{
				request_type: 'store_credential',
				transport: cred.response.getTransports
					? cred.response.getTransports()
					: null,
				client: cred.response.clientDataJSON
					? helper.atb(cred.response.clientDataJSON)
					: null,
				attest: cred.response.attestationObject
					? helper.atb(cred.response.attestationObject)
					: null,
			},
			(res) => {console.log(res)}
		),
};

const validate = {
	a: () =>
		helper.ajax(
			'/wp-admin/admin-ajax.php?action=passkey',
			{
				request_type: 'get_challenge',
				user: 'shub',
				login: 'true',
			},
			async (res) => {
				res = JSON.parse(res);
				helper.bta(res.data.challenge);
				validate.b(
					await navigator.credentials.get(res.data.challenge),
					res.data.user
				);
			}
		),
	b: (cred, user) => {
		console.log(cred);
		helper.ajax(
			'/wp-admin/admin-ajax.php?action=passkey',
			{
				request_type: 'verify_challenge',
				id: cred.rawId ? helper.atb(cred.rawId) : null,
				client: cred.response.clientDataJSON
					? helper.atb(cred.response.clientDataJSON)
					: null,
				auth: cred.response.authenticatorData
					? helper.atb(cred.response.authenticatorData)
					: null,
				sig: cred.response.signature
					? helper.atb(cred.response.signature)
					: null,
				user: cred.response.userHandle
					? helper.atb(cred.response.userHandle)
					: null,
				user_id: user,
			},
			(res) => {console.log(res)}
		);
	},
};
