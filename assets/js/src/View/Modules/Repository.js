
class Repository {

	constructor( vars ) {
		this.vars = vars;
		this.list = [];
	}

	emailList() {
		return new Promise( ( resolve, reject ) => {

			if( this.list.length ) {
				resolve(this.list);
			}

			jQuery.ajax({
				url:this.vars.endpoint,
				data:this.vars.data
			}).done( (response) => {

				this.list = response.data.emailList;
				resolve(this.list);
			}).fail( () => {
				reject();
			});
		} );
	}
}

export default Repository;