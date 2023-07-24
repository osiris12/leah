import TextInput from "@/Components/TextInput.jsx";
import {useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import Logo from "@/Components/Logo.jsx";

export default function Welcome() {
    const [lookupWord, setLookupWord] = useState("");
    const [searchResults, setSearchResults] = useState("");
    console.log(searchResults)

    const fetchData = async (value) => {
        if (value.length <= 1) {
            return false;
        }
        try {
            const response = await axios.get(import.meta.env.VITE_APP_URL + value);
            setSearchResults(response.data);
        } catch (error) {
            console.error("Error fetching searchResults:", error);
        }
    }

    useEffect(() => {}, [lookupWord]);

    const handleSubmit = (lookupWord) => {
        fetchData(lookupWord);
    }

    return (
        <>
            <div className="flex flex-row mb-7 mt-3">
                <div className="basis-1/4 ml-10">
                    <Logo></Logo>
                </div>
                <div className="basis-3/4 self-center break-words p-5 ">
                    <p>
                        A simple and quick Spanish to English translator!
                    </p>
                </div>
            </div>

            <div>
                <div className="flex justify-end">
                    <TextInput
                        className="text-2xl bg-gray-800"
                        placeholder="search"
                        onChange={(e) => setLookupWord(e.target.value)}
                    />
                </div>
                <div className="flex justify-end mt-5">
                    <PrimaryButton
                        className="bg-blue-600"
                        onClick={() => handleSubmit(lookupWord)}
                    > Translate
                    </PrimaryButton>
                </div>

                <div className="ml-3 mr-3 mt-5 grid grid-cols-1 gap-4">
                    {Object.keys(searchResults).map((category) => (
                        <div key={category} className="border-solid mb-5">
                            <div className="flex flex-col items-center">
                                <p><strong>PART OF SPEECH</strong></p>
                                <p>{category}</p>
                            </div>

                            {Object.keys(searchResults[category]).map((verb) => (
                            <div key={verb} className="border border-white border-solid mt-5">
                                <div>
                                    <p><strong>DEFINITION</strong></p>
                                    <h3>{verb}</h3>
                                </div>
                                {Object.keys(searchResults[category][verb]).map((translation) => (
                                    <div key={translation} className="border border-white border-solid grid grid-cols-2">
                                        <div>
                                            <p><strong>TRANSLATION</strong></p>
                                            <h4>{translation}</h4>
                                        </div>
                                        <div>
                                            <p><strong>USAGE EXAMPLES</strong></p>
                                            <p>Spanish: {searchResults[category][verb][translation].spanish}</p>
                                            <p>English: {searchResults[category][verb][translation].english}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                            ))}
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}
