package br.com.epitrack.healthycup.fragments.maisopcoes;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.InflateException;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListView;
import android.widget.ExpandableListView.OnChildClickListener;
import br.com.epitrack.healthycup.R;
import br.com.epitrack.healthycup.adapters.LinkAdapter;

public class LinksFragment extends Fragment {

	public LinksFragment() {

	}

	private static View view;
	ExpandableListView expListView;
	LinkAdapter listAdapter;
	List<String> listDataHeader;
	HashMap<String, List<NameValuePair>> listDataChild;

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
		// prepareListData();
		listDataHeader = new ArrayList<String>();
		listDataChild = new HashMap<String, List<NameValuePair>>();
		listDataHeader.add(getString(R.string.governamentais));
		listDataHeader.add(getString(R.string.naogovernamentais));
		listDataHeader.add(getString(R.string.outros));
		List<NameValuePair> links1 = new ArrayList<NameValuePair>();
		links1.add(new BasicNameValuePair(getString(R.string.saude_viajente), "http://www.saude.gov.br/viajante"));
		links1.add(new BasicNameValuePair(getString(R.string.svs), "http://www.saude.gov.br/svs"));
		links1.add(new BasicNameValuePair(getString(R.string.form_notificacao), "http://formsus.datasus.gov.br/site/formulario.php?id_aplicacao=432"));
		links1.add(new BasicNameValuePair(getString(R.string.min_agricultura), "http://www.agricultura.gov.br/"));
		links1.add(new BasicNameValuePair(getString(R.string.anvisa), "http://www.anvisa.gov.br"));
		links1.add(new BasicNameValuePair(getString(R.string.malalegal), "http://www.agricultura.gov.br/servicos-e-sistemas/servicos/viagens-mala-legal"));
		links1.add(new BasicNameValuePair(getString(R.string.portal_copa), "http://www.copa2014.gov.br/"));
		links1.add(new BasicNameValuePair(getString(R.string.app_gov), "http://www.aplicativos.gov.br/"));
		links1.add(new BasicNameValuePair(getString(R.string.areas_febre_amarela), "http://portalsaude.saude.gov.br/images/pdf/2014/fevereiro/19/Areas-com-recomendacao-para-vacinacao-contra-febre-amarela.pdf "));
		

		
		
		List<NameValuePair> links2 = new ArrayList<NameValuePair>();
		links2.add(new BasicNameValuePair(getString(R.string.HealthMap), "http://healthmap.org/pt/"));
		links2.add(new BasicNameValuePair(getString(R.string.ProMed), "http://www.promedmail.org/pt"));
		links2.add(new BasicNameValuePair(getString(R.string.proteja_gol), "http://unaids.org.br/protejaogol/proteja-o-gol/"));
		links2.add(new BasicNameValuePair(getString(R.string.Fifa), "http://pt.fifa.com/"));
		 
		List<NameValuePair> links3 = new ArrayList<NameValuePair>();
		links3.add(new BasicNameValuePair(getString(R.string.OMS), "http://www.who.int"));

		 
		
		listDataChild.put(listDataHeader.get(0),links1 );
		listDataChild.put(listDataHeader.get(1),links2);
		listDataChild.put(listDataHeader.get(2),links3);
		

		listAdapter = new LinkAdapter(view.getContext(), listDataHeader, listDataChild,R.color.cinza_texbox,R.color.azul_saiba_mais);
		// setting list adapter
		expListView.setAdapter(listAdapter);
		expListView.expandGroup(0);
		
		expListView.setOnChildClickListener(new OnChildClickListener() {
			
			@Override
			public boolean onChildClick(ExpandableListView parent, View v, int groupPosition, int childPosition, long id) {
				List<NameValuePair> url = listDataChild.get(listDataHeader.get(groupPosition));
				Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url.get(childPosition).getValue()));
				startActivity(browserIntent);
				return false;
			}
		});
		
		

		return view;
	}
}
