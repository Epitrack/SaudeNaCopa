package br.com.epitrack.healthycup.classes.factory;

import br.com.epitrack.healthycup.classes.Arena;
import br.com.epitrack.healthycup.classes.arenas.Brasilia;
import br.com.epitrack.healthycup.classes.arenas.Pernambuco;

public class ArenaFactory {
	private int arena;
	public ArenaFactory(int arena){
		this.arena = arena;
	}
	
	public Arena getArena(){
		Arena arenaRetorno=null;
		switch (this.arena) {
		case 0:
			arenaRetorno = new Brasilia();
			break;
		case 1:
			arenaRetorno = new Pernambuco();
			break;

		default:
			break;
		}
		return arenaRetorno;
	}

}
