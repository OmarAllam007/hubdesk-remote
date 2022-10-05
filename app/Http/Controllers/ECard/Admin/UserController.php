<?php

namespace App\Http\Controllers\ECard\Admin;

use App\BusinessCardUser;
use App\BusinessCardUserAdminPermissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;

class UserController extends Controller
{

    public function create()
    {
        if (!$this->canContinue('create')) {
            return view('e-card.admin.unauthorized');
        }

        return view('e-card.admin.user.create');
    }

    public function store(Request $request)
    {
        $user = new BusinessCardUser($request->except('_token'));

        $user['url_code'] = str_random(8);

        if ($request->hasFile('image_url')) {
            $user['image_url'] = $user->upload('image_url');
        }

        $user->save();

        flash(t('User Info'), t('User has been saved'), 'success');

        return \Redirect::route('e-card.admin.user.index');
    }

    public function edit(BusinessCardUser $user)
    {
        if (!$this->canContinue('edit')) {
            return view('e-card.admin.unauthorized');
        }

        return view('e-card.admin.user.edit', compact('user'));
    }

    public function show($user)
    {
        $user = BusinessCardUser::where('url_code', $user)->first();
        return view('e-card.admin.user.show', compact('user'));
    }

    public function update(BusinessCardUser $user, Request $request)
    {
        $data = $request->except('_token');

        if ($request->hasFile('image_url')) {
            $data['image_url'] = $user->upload('image_url');
        }

        if ($request->has('remove_image')) {
            $data['image_url'] = null;
        }

        $user->update($data);

        flash(t('User Info'), t('User has been saved'), 'success');

        return \Redirect::route('e-card.admin.user.index');
    }

    public function destroy(BusinessCardUser $user)
    {
        if (!$this->canContinue('delete')) {
            return view('e-card.admin.unauthorized');
        }

        $user->delete();

        alert('O', 'new', 'error');

        flash(t('User Info'), t('User has been deleted'), 'success');

        return \Redirect::route('e-card.admin.user.index');
    }

    public function downloadCard(BusinessCardUser $user){
        $vcard = new VCard();

        $vcard->addName('',$user->name);

// add work data
        $vcard->addCompany($user->business_unit->name ?? "");
        $vcard->addJobtitle($user->position);
        $vcard->addEmail($user->email);
        $vcard->addPhoneNumber($user->mobile,'TYPE=Mobile');
        $vcard->addPhoneNumber($user->phone, 'TYPE=PHONE');
        $vcard->addURL($user->website,'TYPE=Website');
        $vcard->addURL($user->facebook_url,'TYPE=Facebook');
        $vcard->addURL($user->twitter_url,'TYPE=Twitter');
        $vcard->addURL($user->linkedin_url,'TYPE=LinkedIn');

        $vcard->addPhoto($user->image, true);

        header('Content-Disposition: attachment; filename="filename.vcf"');

        return $vcard->download();
    }

    public function canContinue($permission)
    {
        if (auth()->user()->isAdmin()) {
            return true;
        }
        return BusinessCardUserAdminPermissions::where('user_id', auth()->id())
            ->where($permission, 1)->first();

    }
}
