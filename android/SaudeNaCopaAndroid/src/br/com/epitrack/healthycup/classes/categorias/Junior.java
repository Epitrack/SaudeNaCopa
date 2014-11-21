package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class Junior extends Categoria {

	public Junior() {
		this.imgCategoria = R.drawable.junior_categoria;
		this.imgCategoria2 = R.drawable.cat_junior;
		this.imgTrofeu = R.drawable.junior_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_jr_f_muitobem, R.drawable.cat_jr_f_bem,
				R.drawable.cat_jr_f_normal, R.drawable.cat_jr_f_mal, R.drawable.cat_jr_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_jr_m_muitobem, R.drawable.cat_jr_m_bem,
				R.drawable.cat_jr_m_normal, R.drawable.cat_jr_m_mal, R.drawable.cat_jr_m_muitomal));
	}
}
