<?php

namespace App\Library;

use Illuminate\Http\UploadedFile;
use Storage;
use Image;

/**
 * File upload proccess
 */
class ImgFileUpload
{
	protected $file;
	protected $hashFileName;
	protected $guessExtension;
	protected $filters;
	protected $settings;
	protected $savePath = [];
	
	function __construct(UploadedFile $file, Array $filters = null, Array $settings = null, String $extension = null)
	{
		$this->file = $file;
		$this->filters = $filters;
		$this->settings = $settings;
		$this->hashFileName = explode('.', $file->hashName())[0];
		$this->guessExtension = $extension ?? $file->guessExtension();
	}

	public function saveImg()
	{
		if($this->filters !== null){

			$savePathAndFileName = $this->savePathAndFileName();
			
			foreach ($this->filters as $val) {
				
				$filt = config('parameters.filter.'.$val);
				$img = $this->performFilters($filt);

				Storage::put(
					str_replace('*filtName*', $val, $savePathAndFileName), 
					$img->encode()
				);
			}

		}else if($this->settings !== null){

			$savePathAndFileName = $this->savePathAndFileName();

			foreach ($this->settings as $val) {
				
				$filt = config('parameters.filter.'.$val);
				$img = $this->performFilters($filt);

				Storage::put(
					str_replace('*filtName*', $val, $savePathAndFileName), 
					$img->encode()
				);
			}
		}else{
			$img = Image::make($this->file);
			Storage::put($this->savePathAndFileName('raw'), $img->encode());
		}

		return $this;
	}

	public function performFilters($filter)
	{
		$img = Image::make($this->file);

		foreach ($filter as $key => $val) {

			if (is_array($val)) 
				$img->$key(...$val);
			else
				$img->$key($val);
		}

		return $img;
	}

	public function savePathAndFileName($filtName = '*filtName*')
	{
		$fileName = $this->hashFileName.'.'.$this->guessExtension;
		$pathName = implode('/', [
			date('Y'),
			date('m'),
			date('d'),
			date('H'),
		]);
		$imagesPath = '/public/upload/imgs/'.$filtName;
		
		$pathAndFileName = "{$imagesPath}/{$pathName}/{$fileName}";
		
		$this->setSavePath("/{$pathName}/{$fileName}");

		return $pathAndFileName;
	}

    /**
     * @return mixed
     */
    public function getSavePath()
    {
        return $this->savePath;
    }

    /**
     * @param mixed $savePath
     *
     * @return self
     */
    public function setSavePath($savePath)
    {
        $this->savePath[] = $savePath;

        return $this;
    }
}