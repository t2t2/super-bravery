<?php

namespace t2t2\SuperBravery\Http\Controllers;


class FrontendController extends Controller {

	/**
	 * Serve frontend to the user
	 *
	 * @return \Illuminate\View\View
	 */
	public function serve() {

		return view('frontend');
	}

}