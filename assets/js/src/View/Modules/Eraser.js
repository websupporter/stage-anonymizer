
class Eraser {

	constructor( vars ) {
		this.vars = vars;
		this.eraserCount = this.vars.eraserCount;
	}

	erase( email, eraserIndex, page ) {

		return new Promise( ( resolve, reject ) => {

			const data = this.vars.data;
			data.email = email;
			data.page = page;
			data.eraserIndex = eraserIndex;
			jQuery.ajax({
				url:this.vars.endpoint,
				data:this.vars.data,
				method:'POST'
			}).done( (response) => {
				if(! response.data.done) {
					this.erase(email,eraserIndex,++page).then( () => {
						resolve();
					});
					return;
				}

				if( eraserIndex < this.eraserCount ) {

					this.erase(email,++eraserIndex,1).then( () => {
						resolve();
					});
					return;
				}

				resolve();
			}).fail( () => {
				reject();
			});
		});
	}
}

export default Eraser;