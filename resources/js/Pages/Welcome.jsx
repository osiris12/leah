import TextInput from "@/Components/TextInput.jsx";
import { Link } from "@inertiajs/react";
import {useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton.jsx";

export default function Welcome({words}) {
    const [lookupWord, setLookupWord] = useState("");
    const [searchResults, setSearchResults] = useState("");

    const fetchData = (value) => {
        if(value.length <= 1) {
            return false;
        }
        fetch("http://leah.local/" + value)
            .then((response) => response.json())
            .then((json) => {
                console.log(json);
                setSearchResults(json);
            });
    }

    useEffect(() => {
        console.log(lookupWord);
    }, [lookupWord]);

    const handleSubmit = (lookupWord) => {
        fetchData(lookupWord);
    }

    return (
        <>
            <div className="w-full mb-48"></div>
            <div>
                <div className="flex h justify-end">
                    <TextInput
                        className="text-2xl bg-gray-800"
                        placeholder="search"
                        onChange={(e) => setLookupWord(e.target.value)}
                    />
                </div>
                <div className="flex justify-end mt-2">
                    <PrimaryButton
                        className="bg-blue-600"
                        onClick={(e) => handleSubmit(lookupWord)}
                    > Translate
                    </PrimaryButton>
                </div>
                <div className=" mt-3 bg-gray-600">
                    { searchResults &&
                        <pre>{JSON.stringify(searchResults, null, 2)}</pre>
                    }
                </div>
            </div>
        </>
    );
}
