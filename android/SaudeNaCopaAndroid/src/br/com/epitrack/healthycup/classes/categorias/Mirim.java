package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class Mirim extends Categoria {
	public Mirim() {
		this.imgCategoria = R.drawable.cat_mi;
		this.imgCategoria2 = R.drawable.cat_mirim;
		this.imgTrofeu = R.drawable.cat_mi_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_mi_f_muitobem, R.drawable.cat_mi_f_bem,
				R.drawable.cat_mi_f_normal, R.drawable.cat_mi_f_mal, R.drawable.cat_mi_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_mi_m_muitobem, R.drawable.cat_mi_m_bem,
				R.drawable.cat_mi_m_normal, R.drawable.cat_mi_m_mal, R.drawable.cat_mi_m_muitomal));
	}
}
