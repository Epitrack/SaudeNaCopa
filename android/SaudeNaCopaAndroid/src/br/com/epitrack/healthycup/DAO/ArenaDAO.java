package br.com.epitrack.healthycup.DAO;

import java.util.ArrayList;
import java.util.List;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Arena;
import br.com.epitrack.healthycup.classes.arenas.Amazonas;
import br.com.epitrack.healthycup.classes.arenas.Bahia;
import br.com.epitrack.healthycup.classes.arenas.BeloHorizionte;
import br.com.epitrack.healthycup.classes.arenas.Brasilia;
import br.com.epitrack.healthycup.classes.arenas.Ceara;
import br.com.epitrack.healthycup.classes.arenas.MatoGrosso;
import br.com.epitrack.healthycup.classes.arenas.Parana;
import br.com.epitrack.healthycup.classes.arenas.Pernambuco;
import br.com.epitrack.healthycup.classes.arenas.RioGrandeNorte;
import br.com.epitrack.healthycup.classes.arenas.RioGrandeSul;
import br.com.epitrack.healthycup.classes.arenas.RioJaneiro;
import br.com.epitrack.healthycup.classes.arenas.SaoPaulo;

public class ArenaDAO {
	public ArenaDAO() {

	}

	public List<Arena> getArenas() {
		List<Arena> arenas = new ArrayList<Arena>();
		arenas.add(new MatoGrosso());
		arenas.add(new Parana());
		arenas.add(new RioGrandeNorte());
		
		arenas.add(new Amazonas());
		arenas.add(new Pernambuco());
		arenas.add(new Ceara());
		arenas.add(new RioGrandeSul());
		arenas.add(new Bahia());

		arenas.add(new Brasilia(R.string.estadio_nacional, R.drawable.arena_brasilia));
		arenas.add(new BeloHorizionte(R.string.estadio_mineirao, R.drawable.arena_bh));

		arenas.add(new SaoPaulo());
		arenas.add(new RioJaneiro());
		return arenas;
	}

}
