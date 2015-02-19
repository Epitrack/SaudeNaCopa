package br.com.epitrack.healthycup.classes.categorias;

import java.util.ArrayList;
import java.util.Arrays;

import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.classes.Categoria;

public class DenteDeLeite extends Categoria {

	public DenteDeLeite() {
		this.imgCategoria = R.drawable.denteleite_categoria;
		this.imgCategoria2 = R.drawable.cat_dentedeleite;
		this.imgTrofeu = R.drawable.cat_dl_trofeu;
		this.imagensSentimentosFeminino = new ArrayList<Integer>(Arrays.asList(R.drawable.cat_dl_f_muitobem, R.drawable.cat_dl_f_bem,
				R.drawable.cat_dl_f_normal, R.drawable.cat_dl_f_mal, R.drawable.cat_dl_f_muitomal));
		this.imagensSentimentosMasculino = new ArrayList<Integer>(Arrays.asList(R.drawable.dl_m_muitobem, R.drawable.dl_m_bem,
				R.drawable.dl_m_normal, R.drawable.dl_m_mal, R.drawable.dl_m_muitomal));
	}

}
