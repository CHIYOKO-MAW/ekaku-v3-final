<div>
    <x-input-label for="nik" value="NIK" />
    <x-text-input id="nik" name="nik" type="text" :value="old('nik', $biodata->nik ?? '')" required />
    <x-input-error :messages="$errors->get('nik')" />
</div>

<div>
    <x-input-label for="tempat_lahir" value="Tempat Lahir" />
    <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" :value="old('tempat_lahir', $biodata->tempat_lahir ?? '')" required />
    <x-input-error :messages="$errors->get('tempat_lahir')" />
</div>

<div>
    <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" :value="old('tanggal_lahir', $biodata->tanggal_lahir ?? '')" required />
    <x-input-error :messages="$errors->get('tanggal_lahir')" />
</div>

<div>
    <x-input-label for="alamat" value="Alamat" />
    <textarea id="alamat" name="alamat" class="form-input" required>{{ old('alamat', $biodata->alamat ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('alamat')" />
</div>

<div>
    <x-input-label for="pendidikan" value="Pendidikan" />
    <x-text-input id="pendidikan" name="pendidikan" type="text" :value="old('pendidikan', $biodata->pendidikan ?? '')" required />
    <x-input-error :messages="$errors->get('pendidikan')" />
</div>

<div>
    <x-input-label for="keahlian" value="Keahlian" />
    <x-text-input id="keahlian" name="keahlian" type="text" :value="old('keahlian', $biodata->keahlian ?? '')" />
    <x-input-error :messages="$errors->get('keahlian')" />
</div>
