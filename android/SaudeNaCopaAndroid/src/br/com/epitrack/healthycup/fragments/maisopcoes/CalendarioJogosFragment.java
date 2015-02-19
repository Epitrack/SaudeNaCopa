package br.com.epitrack.healthycup.fragments.maisopcoes;

import java.text.ParseException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListAdapter;
import android.widget.ExpandableListView;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.adapters.CalendarioJogosAdapter;
import br.com.epitrack.healthycup.classes.Jogo;

public class CalendarioJogosFragment extends Fragment {

	public CalendarioJogosFragment() {

	}

	private static View view;
	ExpandableListAdapter listAdapter;
	ExpandableListView expListView;
	List<String> listDataHeader;
	HashMap<String, List<Jogo>> listDataChild;

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

		if (view != null) {
			ViewGroup parent = (ViewGroup) view.getParent();
			if (parent != null)
				parent.removeView(view);
		}
		try {
			view = inflater.inflate(R.layout.fragment_consulado, container, false);
		} catch (InflateException e) {
			/* map is already there, just return view as it is */
		}
		expListView = (ExpandableListView) view.findViewById(R.id.lvExp);
		try {
			prepareListData();
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		listAdapter = new CalendarioJogosAdapter(view.getContext(), listDataHeader, listDataChild);

		// setting list adapter
		expListView.setAdapter(listAdapter);

		return view;
	}

	private void prepareListData() throws ParseException {
		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, List<Jogo>>();

		listDataHeader.add(getString(R.string.fase_grupos));
		listDataHeader.add(getString(R.string.fase_oitava));
		listDataHeader.add(getString(R.string.fase_quarta));
		listDataHeader.add(getString(R.string.fase_semi));
		listDataHeader.add(getString(R.string.fase_3lugar));
		listDataHeader.add(getString(R.string.fase_final));

		// fase grupos

		List<Jogo> jogosGrupos = new ArrayList<Jogo>();
		
		// GRUPO A
		jogosGrupos.add(new Jogo( getString(R.string.grupo)+ " A", getString(R.string.rodada)+ " 01", getString(R.string.brasil)+" X "+getString(R.string.croacia), "12/06/2014 - 17:00",getString(R.string.arena_sao_paulo)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.mexico)+" X "+getString(R.string.camarao), "13/06/2014 - 13:00",getString(R.string.estadio_dunas)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.brasil)+" X "+getString(R.string.mexico), "17/06/2014 - 16:00",getString(R.string.estadio_castelao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.croacia)+" X "+getString(R.string.camarao), "18/06/2014 - 19:00",getString(R.string.arena_amazonia)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.camarao)+" X "+getString(R.string.brasil), "23/06/2014 - 17:00",getString(R.string.estadio_nacional)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.croacia)+" X "+getString(R.string.mexico), "23/06/2014 - 17:00",getString(R.string.arena_pernambuco)));
		
		// GRUPO B
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " B", getString(R.string.rodada)+ " 01", getString(R.string.espanha)+" X "+getString(R.string.holanda), "13/06/2014 - 16:00",getString(R.string.arena_fonte_nova)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.chile)+" X "+getString(R.string.australia), "13/06/2014 - 19:00",getString(R.string.arena_pantanal)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.australia)+" X "+getString(R.string.holanda), "18/06/2014 - 13:00",getString(R.string.estadio_beira_rio)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.espanha)+" X "+getString(R.string.chile), "18/06/2014 - 16:00",getString(R.string.estadio_maracana)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.australia)+" X "+getString(R.string.espanha), "23/06/2014 - 13:00",getString(R.string.arena_baixada)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.holanda)+" X "+getString(R.string.chile), "23/06/2014 - 13:00",getString(R.string.arena_sao_paulo)));

		// GRUPO C
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " C", getString(R.string.rodada)+ " 01", getString(R.string.colombia)+" X "+getString(R.string.grecia), "14/06/2014 - 13:00",getString(R.string.estadio_mineirao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.costamarfin)+" X "+getString(R.string.japao), "14/06/2014 - 22:00",getString(R.string.arena_pernambuco)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.colombia)+" X "+getString(R.string.costamarfin), "19/06/2014 - 13:00",getString(R.string.estadio_nacional)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.japao)+" X "+getString(R.string.grecia), "19/06/2014 - 19:00",getString(R.string.estadio_dunas)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.grecia)+" X "+getString(R.string.costamarfin), "24/06/2014 - 17:00",getString(R.string.estadio_castelao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.japao)+" X "+getString(R.string.colombia), "24/06/2014 - 17:00",getString(R.string.arena_pantanal)));

		// GRUPO D
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " D", getString(R.string.rodada)+ " 01", getString(R.string.uruguai)+" X "+getString(R.string.costarica), "14/06/2014 - 16:00",getString(R.string.estadio_castelao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.inglaterra)+" X "+getString(R.string.italia), "14/06/2014 - 19:00",getString(R.string.arena_amazonia)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.uruguai)+" X "+getString(R.string.inglaterra), "19/06/2014 - 16:00",getString(R.string.arena_sao_paulo)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.italia)+" X "+getString(R.string.costarica), "20/06/2014 - 13:00",getString(R.string.arena_pernambuco)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.costarica)+" X "+getString(R.string.inglaterra), "24/06/2014 - 13:00",getString(R.string.estadio_mineirao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.italia)+" X "+getString(R.string.uruguai), "24/06/2014 - 13:00",getString(R.string.estadio_dunas)));

		// GRUPO E
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " E", getString(R.string.rodada)+ " 01", getString(R.string.suica)+" X "+getString(R.string.equador), "15/06/2014 - 13:00",getString(R.string.estadio_nacional)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.franca)+" X "+getString(R.string.honduras), "15/06/2014 - 16:00",getString(R.string.estadio_beira_rio)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.suica)+" X "+getString(R.string.franca), "20/06/2014 - 16:00",getString(R.string.arena_fonte_nova)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.honduras)+" X "+getString(R.string.equador), "20/06/2014 - 19:00",getString(R.string.arena_baixada)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.equador)+" X "+getString(R.string.franca), "25/06/2014 - 17:00",getString(R.string.estadio_maracana)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.honduras)+" X "+getString(R.string.suica), "25/06/2014 - 17:00",getString(R.string.arena_amazonia)));

		// GRUPO F
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " F", getString(R.string.rodada)+ " 01", getString(R.string.argentina)+" X "+getString(R.string.bosnia), "15/06/2014 - 19:00",getString(R.string.estadio_maracana)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.iran)+" X "+getString(R.string.nigeria), "16/06/2014 - 16:00",getString(R.string.arena_baixada)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.argentina)+" X "+getString(R.string.iran), "21/06/2014 - 13:00",getString(R.string.estadio_mineirao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.nigeria)+" X "+getString(R.string.bosnia), "21/06/2014 - 19:00",getString(R.string.arena_pantanal)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.bosnia)+" X "+getString(R.string.iran), "25/06/2014 - 13:00",getString(R.string.arena_fonte_nova)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.nigeria)+" X "+getString(R.string.argentina), "25/06/2014 - 13:00",getString(R.string.estadio_beira_rio)));

		// GRUPO G
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " G", getString(R.string.rodada)+ " 01", getString(R.string.alemanha)+" X "+getString(R.string.portugal), "16/06/2014 - 13:00",getString(R.string.arena_fonte_nova)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.gana)+" X "+getString(R.string.usa), "16/06/2014 - 19:00",getString(R.string.estadio_dunas)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.alemanha)+" X "+getString(R.string.gana), "21/06/2014 - 16:00",getString(R.string.estadio_castelao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.usa)+" X "+getString(R.string.portugal), "22/06/2014 - 19:00",getString(R.string.arena_amazonia)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.portugal)+" X "+getString(R.string.gana), "26/06/2014 - 13:00",getString(R.string.estadio_nacional)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.usa)+" X "+getString(R.string.alemanha), "26/06/2014 - 13:00",getString(R.string.arena_pernambuco)));

		// GRUPO H
		jogosGrupos.add(new Jogo(getString(R.string.grupo)+ " H", getString(R.string.rodada)+ " 01", getString(R.string.belgica)+" X "+getString(R.string.argelia), "17/06/2014 - 13:00",getString(R.string.estadio_mineirao)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.russia)+" X "+getString(R.string.korea), "17/06/2014 - 19:00",getString(R.string.arena_pantanal)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 02", getString(R.string.belgica)+" X "+getString(R.string.russia), "22/06/2014 - 13:00",getString(R.string.estadio_maracana)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.korea)+" X "+getString(R.string.argelia), "22/06/2014 - 16:00",getString(R.string.estadio_beira_rio)));

		jogosGrupos.add(new Jogo("", getString(R.string.rodada)+ " 03", getString(R.string.argelia)+" X "+getString(R.string.russia), "26/06/2014 - 17:00",getString(R.string.arena_baixada)));
		jogosGrupos.add(new Jogo("", "", getString(R.string.korea)+" X "+getString(R.string.belgica), "26/06/2014 - 17:00",getString(R.string.arena_sao_paulo)));

		List<Jogo> jogosOitavas = new ArrayList<Jogo>();
		jogosOitavas.add(new Jogo("", getString(R.string.fase_oitava), "1º A X 2º B", "28/06/2014 - 13:00",getString(R.string.estadio_mineirao)));
		jogosOitavas.add(new Jogo("", "", "1º C X 2º D", "28/06/2014 - 17:00",getString(R.string.estadio_maracana)));
		jogosOitavas.add(new Jogo("", "", "1º E X 2º F", "30/06/2014 - 13:00",getString(R.string.estadio_nacional)));
		jogosOitavas.add(new Jogo("", "", "1º G X 2º H", "30/06/2014 - 17:00",getString(R.string.estadio_beira_rio)));
		jogosOitavas.add(new Jogo("", "", "1º B X 2º A", "29/06/2014 - 13:00",getString(R.string.estadio_castelao)));
		jogosOitavas.add(new Jogo("", "", "1º D X 2º C", "29/06/2014 - 17:00",getString(R.string.arena_pernambuco)));
		jogosOitavas.add(new Jogo("", "", "1º F X 2º E", "01/07/2014 - 13:00",getString(R.string.arena_sao_paulo)));
		jogosOitavas.add(new Jogo("", "", "1º H X 2º G", "01/07/2014 - 17:00",getString(R.string.arena_fonte_nova)));

		List<Jogo> jogosQuartas = new ArrayList<Jogo>();
		jogosQuartas.add(new Jogo("", getString(R.string.fase_quarta), "1º A ou 2º B X 1º C ou 2º D", "04/07/2014 - 17:00",getString(R.string.estadio_castelao)));
		jogosQuartas.add(new Jogo("", "", "1º E ou 2º F X 1º G ou 2º H", "04/07/2014 - 13:00",getString(R.string.estadio_maracana)));
		jogosQuartas.add(new Jogo("", "", "1º B ou 2º A X 1º D ou 2º C",  "05/07/2014 - 17:00",getString(R.string.arena_fonte_nova)));
		jogosQuartas.add(new Jogo("", "", "1º F ou 2º E X 1º H ou 2º G",  "05/07/2014 - 13:00",getString(R.string.estadio_nacional)));

		List<Jogo> jogosSemi = new ArrayList<Jogo>();
		jogosSemi.add(new Jogo("", getString(R.string.fase_semi), getString(R.string.vencedor_quartas)+" 1 X "+getString(R.string.vencedor_quartas)+" 2", "08/07/2014 - 17:00",getString(R.string.estadio_mineirao)));
		jogosSemi.add(new Jogo("", "",  getString(R.string.vencedor_quartas)+" 3 X "+getString(R.string.vencedor_quartas)+" 4", "09/07/2014 - 17:00",getString(R.string.arena_sao_paulo)));
		
		List<Jogo> jogos3Lugar = new ArrayList<Jogo>();
		jogos3Lugar.add(new Jogo("", getString(R.string.fase_3lugar), getString(R.string.perdedor_semi)+" 1 X "+getString(R.string.perdedor_semi)+" 2",  "12/07/2014 - 17:00",getString(R.string.estadio_nacional)));

		List<Jogo> jogosFinal = new ArrayList<Jogo>();
		jogosFinal.add(new Jogo("", getString(R.string.fase_final), getString(R.string.vencedor_semi)+" 1 X "+getString(R.string.vencedor_semi)+" 2", "13/07/2014 - 16:00",getString(R.string.estadio_maracana)));
		// colocar a descricao
		listDataChild.put(listDataHeader.get(0), jogosGrupos);
		listDataChild.put(listDataHeader.get(1), jogosOitavas);
		listDataChild.put(listDataHeader.get(2), jogosQuartas);
		listDataChild.put(listDataHeader.get(3), jogosSemi);
		listDataChild.put(listDataHeader.get(4), jogos3Lugar);
		listDataChild.put(listDataHeader.get(5), jogosFinal);
	}

}
