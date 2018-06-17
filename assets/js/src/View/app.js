import View from './Modules/View';
import Repository from './Modules/Repository';
import Eraser from './Modules/Eraser';


jQuery( document ).ready( () => {
	"use strict";

	const eraser = new Eraser(StageAnonymizerVars.anonymizer);
	const emailList = new Repository(StageAnonymizerVars.emailList);
	const view = new View(StageAnonymizerVars.emailList, emailList,eraser);
	view.init();
});
