
class View {

	constructor( vars, repository, eraser ) {
		this.vars = vars;
		this.repository = repository;
		this.eraser = eraser;
		this.processedMails = 0;
		this.emailList = [];
		this.button = null;
	}

	init() {
		this.button = jQuery( '<button id="stage-anonymizer">Stage Anonymizer</button>' );
		this.button.appendTo( '#wpbody-content>.wrap' );
		this.button.click( (event) => {
			event.preventDefault();
			this.repository.emailList().then( (data) => {
				this.processedMails = 0;
				this.emailList = data;
				this.processList();
			});
		});
	}

	processList() {
		if( this.button === null ) {
			return false;
		}
		if( ! this.emailList.length || this.emailList.length === this.processedMails ) {
			return this.processListDone();
		}

		this.button.text(this.processedMails + '/' + this.emailList.length);

		const email = this.emailList[ this.processedMails ];
		console.log(email);
		console.log(this.processedMails);
		console.log(this.emailList);
		this.eraser.erase( email, 1, 1 ).then( () => {
			this.processedMails++;
			return this.processList();
		});
	}

	processListDone() {
		this.button.text( this.vars.text.finished );
		return true;
	}
};

export default View;