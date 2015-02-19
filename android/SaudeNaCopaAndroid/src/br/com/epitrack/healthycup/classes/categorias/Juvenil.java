package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class Juvenil extends Categoria {

	public Juvenil() {
		this.imgCategoria = R.drawable.juvenil_categoria;
		this.imgCategoria2 = R.drawable.cat_juvenil;
		this.imgTrofeu = R.drawable.juvenil_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_ju_f_muitobem, R.drawable.cat_ju_f_bem,
				R.drawable.cat_ju_f_normal, R.drawable.cat_ju_f_mal, R.drawable.cat_ju_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_ju_m_muitobem, R.drawable.cat_ju_m_bem,
				R.drawable.cat_ju_m_normal, R.drawable.cat_ju_m_mal, R.drawable.cat_ju_m_muitomal));
	}
}
